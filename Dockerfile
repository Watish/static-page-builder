FROM watish/alpine:base
RUN mkdir /opt/app -p
RUN apk add php8 php8-tokenizer
RUN apk add composer
RUN ln -sf /usr/bin/php8 /usr/bin/php

RUN php -v

RUN mkdir /opt/content
VOLUME /opt/app/content

COPY . /opt/app

RUN cd /opt/app && chmod 777 . -R

ENTRYPOINT /opt/app/entrypoint.sh