# JavaScript projects

Collection of earlier web applications (mostly PHP/JS), being modernized step by step to JavaScript/TypeScript.

## Repository layout

| Path          | Content                                                                                                                                     |
| ------------- | ------------------------------------------------------------------------------------------------------------------------------------------- |
| `apps/`       | All applications, each with `legacy/` (original state) and later `modernized/`                                                              |
| `apps/games/` | Browser games: bierspiel (v5, v6), hoehlenmensch                                                                                            |
| `apps/web/`   | Web apps: iusemybrain, manageyourteam, verwaltungstool, greenscreen, unser-blog, family-adventskalender, procumer-dashboard, family-library |
| `apps/sites/` | Static sites: hallindi, kia                                                                                                                 |
| `apps/misc/`  | Small projects: email-check, richkidzofupo, zisterne                                                                                        |
| `docs/`       | Analyses, migration plans and status logs                                                                                                   |
| `tools/`      | Helper tools for analysis and migration                                                                                                     |
| `PLAN.md`     | Task plan per app, followed and ticked off by every session                                                                                 |
| `AGENTS.md`   | Context for coding agents                                                                                                                   |

## Status

| App                        | Legacy                                   | Modernized |
| -------------------------- | ---------------------------------------- | ---------- |
| games/bierspiel            | v5, v6                                   | done       |
| games/hoehlenmensch        | yes                                      | done       |
| web/iusemybrain            | yes (`bootstrap` was identical, deleted) | done       |
| web/manageyourteam         | yes (`legacy-neu` was identical)         | done       |
| web/verwaltungstool        | yes                                      | done       |
| web/greenscreen            | yes                                      | done       |
| web/unser-blog             | yes                                      | done       |
| web/family-adventskalender | yes (CI/Docker/GitLab files removed)     | done       |
| web/procumer-dashboard     | yes (private XLSX and `.env` removed)    | done       |
| web/family-library         | yes (`.env` files replaced)              | done       |
| sites/hallindi             | yes                                      | done       |
| sites/kia                  | yes                                      | done       |
| misc/email-check           | yes                                      | done       |
| misc/richkidzofupo         | yes                                      | done       |
| misc/zisterne              | yes (none found)                         | done       |
