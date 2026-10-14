# family-library

Source: `gitlab-export/family-library-master/` (159 files). Private project by Markus Allwang (MIT): a family book shelf ("Bücherregal") where verified users manage books and admins manage users. Lerna monorepo with an Angular 9 frontend (Clarity UI) and a NestJS 6 + MongoDB backend. Will live in `apps/web/family-library/`.

The sibling export `family-library-app-master` (Ionic 5 / Angular 9 tabs starter, 61 files, no custom code) was a skeleton and was deleted.

## Inspect

| Area     | Files                                                                                                                                                              |
| -------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Backend  | `components/backend/src/`: `main.ts`, `app.module.ts`, `config/`, `auth/` (service, controller, local + JWT strategies, roles guard), `user/`, `book/`, `filters/` |
| Frontend | `components/frontend/src/app/`: `pages/{login,inventory,inventory-details,admin,reset-password,help}`, `core/` (auth, book, user services, guards, interceptors)   |
| Tooling  | Lerna, Jest (backend), Karma/Protractor (frontend), compodoc, husky, commitlint, Prettier, ESLint                                                                  |
| Ops      | `docker/app/Dockerfile` (Node 12), `.gitlab-ci.yml` (dependency check, test, build, kaniko image, deploy), `setup-local-environment.sh`, GitLab issue/MR templates |
| Secrets  | `components/backend/src/.env` and `components/frontend/.env` (**content not read**; must be removed/replaced before the legacy commit)                             |

Assets: only `favicon.ico`; no images.

## Purpose and features

- **Registration and login:** self-registration (role forced to `user` by the frontend), e-mail verification link, login returns a JWT (`access_token`, `Bearer`), password reset by e-mail link, resend verification mail. Unverified users cannot log in.
- **Books (`/buecherregal`, any logged-in user):** datagrid with sort, create/edit modal, delete confirmation, detail page showing the owner. Fields: title, author, ISBN (regex-validated in the frontend), year (1900..current), language; `content` and `coverImage` exist but their form fields are disabled (unused).
- **Administration (`/administration`, role `admin`):** list, create, edit, delete users.
- **Help (`/hilfe`):** static page.
- UI language is German.

## API (prefix `/v1`)

| Route                                                                                                                                       | Auth       | Purpose                                                 |
| ------------------------------------------------------------------------------------------------------------------------------------------- | ---------- | ------------------------------------------------------- |
| `POST /auth/register`, `POST /auth/login`                                                                                                   | public     | create user + verification mail, login (passport-local) |
| `GET /auth/verify?email&token`, `GET /auth/forgot-password?email`, `POST /auth/reset-password?token`, `GET /auth/resend-verification?email` | public     | e-mail flows                                            |
| `GET /auth`                                                                                                                                 | JWT        | validate token, return payload user                     |
| `GET/POST/PUT/DELETE /books[/:id]`                                                                                                          | JWT        | book CRUD (all users, all books)                        |
| `GET /users/current`                                                                                                                        | JWT        | current user                                            |
| `GET/POST/PUT/DELETE /users[/:id]`                                                                                                          | JWT, admin | user CRUD                                               |

Also Swagger at `/api`, health check (terminus), helmet, rate limit 1000 req / 15 min, and the backend serves the built Angular app statically (404 filter returns `404.html`/index for the Angular router).

## Request flow

Angular (`environment.backendBaseUrl`, generated from `.env` by `scripts/setup-angular-environment.js`) -> HTTP interceptors (attach `Authorization: Bearer`, global error handling, cancel in-flight requests on navigation) -> NestJS controller -> `JwtAuthGuard` (+ `RolesGuard` via `@Roles('admin')`) -> service -> Mongoose. The token lives in localStorage (`angular-2-local-storage`) and is decoded client-side with `jwt-decode` for id/email/role and expiry.

## Data model (derived for SQLite)

- `user(id, first_name, last_name, email UNIQUE, password_hash, role CHECK in ('admin','user'), verified, verification_token, reset_password_token, updated_at)`
- `book(id, title, author, content, isbn UNIQUE, year, language, cover_image, owner_id -> user(id), updated_at)`

MongoDB ObjectIds become integer ids. No data migration is needed (no data in the repo).

## External services

- MongoDB (`MONGODB_URI`) -> replaced by SQLite.
- SMTP via nodemailer for verification and reset mails. Config keys: `EMAIL_HOST`, `EMAIL_PORT`, `EMAIL_SECURE`, `EMAIL_USER`, `EMAIL_PASS`.
- Other `.env` keys: `NODE_ENV`, `BASE_DOMAIN`, `HOST`, `PORT`, `FRONTEND_PORT`, `API_VERSION`, `SWAGGER_API_ENDPOINT`, `FRONTEND_STATIC_DIR`, `OAUTH_TOKEN_EXPIRATION_MINUTES`, `OAUTH_TOKEN_SECRET_KEY`.

## Issues

Security:

- **Privilege escalation:** `POST /auth/register` is public and accepts `role` from the body (`UserDto.role`), so anyone can register as `admin`. The frontend only hardcodes `user`. The same endpoint accepts `verified` and the tokens.
- **No ownership checks:** any logged-in user can update or delete any book, and set any `ownerId`.
- **Plaintext password on admin update:** `PUT /users/:id` (admin) can set `password` as plaintext (no hashing in `UserService.update`), unlike `register`/`reset-password`.
- **Weak tokens:** verification and reset tokens come from `Math.random()` (not cryptographically secure), never expire, are stored in plaintext, and reset tokens are reused until used.
- **Account enumeration and mail abuse:** `forgot-password` and `resend-verification` are unauthenticated GET endpoints that reveal whether an e-mail exists/is verified.
- **GET with side effects** (verify, forgot-password, resend) and tokens in query strings.
- **HTML injection:** mail bodies interpolate `firstName` unescaped; the verify page is built from config only.
- `ConfigService` logs `MONGODB_URI` (with credentials) at debug level; CORS `origin: '*'`; JWT in localStorage (XSS-readable); JWT `audience: ''`; CSRF and strict `ValidationPipe` options are commented out (no `whitelist`, so extra body fields pass through, which enables the escalation above).
- `.env` files are tracked in the repo.

Quality and dead code:

- Outdated stack (Node 12, NestJS 6, Angular 9, TypeScript 3.7, Mongoose 5, helmet 3, bcrypt 5.0, Protractor, TSLint remnants); `compodoc`, Lerna and husky are tooling noise.
- `updatedAt` defaults to `new Date().getTime()` evaluated once at startup, and is never updated.
- Book `content` and `coverImage` are unused in the UI.
- Most frontend `.spec.ts` files are generated stubs; backend has one controller spec, one config spec and an e2e stub.
- Frontend and backend duplicate form validation rules only partially (ISBN regex only in the frontend; the backend only checks `IsNotEmpty`).

## Open questions for the stack decision

- Stack is decided: Next.js + TypeScript + SQLite, same as `procumer-dashboard` (see `PLAN.md`). To be decided during modernization:
  - Auth: session cookie (httpOnly) instead of a localStorage JWT, password hashing (bcrypt or argon2), whether self-registration stays open or becomes invite/admin-only.
  - Mail: keep nodemailer with SMTP placeholders; in dev log mails to the console when SMTP is not configured.
  - Whether to implement `content`/`coverImage` (default: drop them).
