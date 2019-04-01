CREATE TABLE IF NOT EXISTS `users`
(
    `id`              int(11)      NOT NULL AUTO_INCREMENT,
    `login`           varchar(256) NOT NULL,
    `password`        varchar(256) NOT NULL,
    `email`           varchar(256) NOT NULL,
    `created`         timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `token`           varchar(256)  NULL,
    `token_expire_at` timestamp    NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
