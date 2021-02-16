#! /bin/bash

# Default environment variables

export SYMFONY_ENV=${SYMFONY_ENV:=prod}

# ${SYMFONY database default configuration
export SYMFONY__DATABASE__DRIVER=${SYMFONY__DATABASE__DRIVER:=pdo_mysql}
export SYMFONY__DATABASE__PATH=${SYMFONY__DATABASE__PATH:=null}
export SYMFONY__DATABASE__HOST=${SYMFONY__DATABASE__HOST:=db}
export SYMFONY__DATABASE__PORT=${SYMFONY__DATABASE__PORT:=3306}
export SYMFONY__DATABASE__NAME=${SYMFONY__DATABASE__NAME:=elkarbackup}
export SYMFONY__DATABASE__USER=${SYMFONY__DATABASE__USER:=root}
export SYMFONY__DATABASE__PASSWORD=${SYMFONY__DATABASE__PASSWORD:=root}

# SYMFONY mailer default configuration
export SYMFONY__MAILER__TRANSPORT=${SYMFONY__MAILER__TRANSPORT:=smtp}
export SYMFONY__MAILER__HOST=${SYMFONY__MAILER__HOST:=localhost}
export SYMFONY__MAILER__USER=${SYMFONY__MAILER__USER:=null}
export SYMFONY__MAILER__PASSWORD=${SYMFONY__MAILER__PASSWORD:=null}
export SYMFONY__MAILER__FROM=${SYMFONY__MAILER__FROM:=null}

# Elkarbackup default configuration
export SYMFONY__EB__SECRET=${SYMFONY__EB__SECRET:=fba546d6ab6abc4a01391d161772a14e093c7aa2}
export SYMFONY__EB__UPLOAD__DIR=${SYMFONY__EB__UPLOAD__DIR:=/app/uploads}
export SYMFONY__EB__BACKUP__DIR=${SYMFONY__EB__BACKUP__DIR:=/app/backups}
export SYMFONY__EB__TMP__DIR=${SYMFONY__EB__TMP__DIR:=/app/tmp}
export SYMFONY__EB__URL__PREFIX=${SYMFONY__EB__URL__PREFIX:=null}
export SYMFONY__EB__PUBLIC__KEY=${SYMFONY__EB__PUBLIC__KEY:=/app/.ssh/id_rsa.pub}
export SYMFONY__EB__TAHOE__ACTIVE=${SYMFONY__EB__TAHOE__ACTIVE:=false}
export SYMFONY__EB__MAX__PARALLEL__JOBS=${SYMFONY__EB__MAX__PARALLEL__JOBS:=1}
export SYMFONY__EB__POST__ON__PRE__FAIL=${SYMFONY__EB__POST__ON__PRE__FAIL:=true}
