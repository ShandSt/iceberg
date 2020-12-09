FROM nginx:alpine

RUN addgroup -S -g 1000 iceberg && adduser -S -D -H -u 1000 iceberg -G iceberg && \
    rm /etc/nginx/conf.d/default.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY iceberg.conf /etc/nginx/conf.d/iceberg.conf
