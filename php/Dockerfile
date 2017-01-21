FROM niisan/phpdev

COPY ./ /var/dev

WORKDIR /var/dev

RUN composer install

CMD ["php","deploy.php"]
