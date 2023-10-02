FROM registry.gitlab.com/altinity-public/container-images/clickhouse_debezium_embedded:latest

RUN apt-get update \
    && apt-get install gettext-base \
    && rm -rf \
    /var/cache/apt/archives \
    /var/lib/apt/lists/* \
    /var/cache/* \
    /usr/share/doc/* \
    && echo "Envsubst installed"

COPY db/sink/bin/app.sh .

COPY db/sink/config.template .

ENTRYPOINT ["sh", "app.sh"]
