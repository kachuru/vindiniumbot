FROM php:7.1-cli

COPY ./ /app
WORKDIR /app

ENV PHP_TIMEZONE=Europe/London

# Install dependencies
ARG GITHUB_TOKEN=
