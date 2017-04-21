FROM php:7.0-cli
RUN mkdir /php
COPY ./ /php/
CMD php -S 0:9001 -t /php/web/
EXPOSE 9001