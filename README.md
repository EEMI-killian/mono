This is the monorepo of Shape of you 💅

## Project

- [Api](./api/README.md)
- [App](./app/README.md)

## requirements

start the front

```bash
cd app
yarn dev
```

start the back

```bash
cd api
symfony server:start
```

start the db and redis server

```bash
docker-compose up -d
```
