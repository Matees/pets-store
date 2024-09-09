# Stage 1: Build the Vue.js application
FROM node:16 AS build-stage

# Set working directory
WORKDIR /app
RUN mkdir -p /app/node_modules/.vite
RUN chmod -R 777 /app/node_modules

# Install dependencies
COPY fe/package*.json ./
RUN npm install

# Copy the rest of the application
COPY fe .

# Build the application
RUN npm run build

FROM node:20.12.1-alpine3.19

WORKDIR /app
COPY --chown=node:node --from=build-stage /app/node_modules ./node_modules
COPY fe .

EXPOSE 3001

CMD ["npm" , "run", "dev"]
