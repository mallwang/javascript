FROM node:12.14.0-alpine
WORKDIR /app
ARG npm_registry=https://registry.npmjs.org

COPY --chown=node:node . .
RUN npm install --production --registry ${npm_registry}

RUN rm *.json

USER node
EXPOSE 3000
ENV NODE_ENV=production

CMD ["node", "backend/src/main.js"]
