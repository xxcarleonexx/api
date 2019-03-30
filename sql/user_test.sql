CREATE TABLE IF NOT EXISTS `user_tasks`
(
    `id`          int(11)      NOT NULL AUTO_INCREMENT,
    `name`        varchar(256) NOT NULL,
    `status`      tinyint(1)   NOT NULL DEFAULT 1,
    `user_id`     int(11)      NULL,
    `created`     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8;
