FROM debian:latest

ENV PHP_BRANCH PHP-7.0.0

RUN  apt-get update \
  && packages=" \
    build-essential \
    make \
    autoconf \
    bison \
    re2c \
    openssl \
    pkg-config \
    libcurl4-openssl-dev \
    libmcrypt-dev \
    libxml2-dev \
    libltdl-dev \
    libpng-dev \
    libjpeg-dev \
    libjpeg62 \
    libfreetype6-dev \
    libmysqlclient-dev \
    libpspell-dev \
    libicu-dev \
    libxpm-dev \
    librecode-dev \
    libssl-dev \
    libz-dev \
    libbz2-dev \
    libdb-dev \
    libedit-dev \
    libgdbm-dev \
    libxslt-dev \
    libldb-dev \
    libpq-dev \
    libfcgi-dev \
    zlib1g-dbg \
    libreadline-dev \
    systemtap-sdt-dev \
    unixodbc-dev \
  " \
  && apt-get install -y --no-install-recommends $packages \
  && TMP_FOLDER=/php7-binaries \
  && FPM_USER=root \
  && mkdir -p ${TMP_FOLDER} \
  && cd ${TMP_FOLDER} \
  && wget "https://github.com/php/php-src/archive/${PHP_BRANCH}.tar.gz" \
  && tar -zxvf ${PHP_BRANCH}.tar.gz \
  && cd php-src-${PHP_BRANCH} \
  && ./buildconf --force \
  && CONFIGURE_STRING=" \
    --prefix=/usr/local/php7/7.0.0 \
    --localstatedir=/usr/local/var \
    --sysconfdir=/usr/local/etc/php/7 \
    --with-config-file-path=/usr/local/etc/php/7 \
    --with-config-file-scan-dir=/usr/local/etc/php/7/conf.d \
    --without-pear \
    --mandir=/usr/local/php7/7.0.0/share/man \
    --enable-bcmath \
    --enable-calendar \
    --enable-exif \
    --enable-ftp \
    --enable-gd-native-ttf \
    --enable-intl \
    --enable-mbregex \
    --enable-mbstring \
    --enable-shmop \
    --enable-soap \
    --enable-phar \
    --enable-sockets \
    --enable-sysvmsg \
    --enable-sysvsem \
    --enable-sysvshm \
    --enable-wddx \
    --enable-zip \
      --with-freetype-dir=/usr/local/opt/freetype \
      --with-gd \
      --with-gettext=/usr/local/opt/gettext \
      --with-iconv-dir=/usr \
      --with-icu-dir=/usr \
      --with-jpeg-dir=/usr/local/opt/jpeg \
      --with-kerberos=/usr \
      --with-libedit \
      --with-mhash \
      --with-openssl \
      --with-zlib \
      --with-bz2 \
      --with-pdo-odbc=unixODBC,/usr \
      --with-png-dir=/usr \
      --libexecdir=/usr/local/php7/7.0.0/libexec \
      --with-curl \
      --with-xsl=/usr \
      --with-mcrypt \
      --with-mhash \
      --with-readline \
      --with-mysql \
      --with-pdo-mysql \
      --with-mysqli \
      --with-pgsql \
      --with-pdo-pgsql \
    --disable-debug \
    --enable-pcntl \
      --with-pspell \
    --disable-opcache \
    --enable-dtrace \
    --disable-phpdbg \
    --enable-embedded-mysqli \
    --enable-zend-signals \
    --enable-fpm \
      --with-fpm-user=${FPM_USER} \
      --with-fpm-group=${FPM_USER} \
  " \
  && ./configure ${CONFIGURE_STRING} \
  && make \
  && make install \
  && wget http://pear.php.net/go-pear.phar \
  && /usr/local/php7/7.0.0/bin/php go-pear.phar \
  && ln -s /usr/local/php7/7.0.0/bin/* /usr/bin/ \
  && apt-get autoremove --purge \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* ${TMP_FOLDER}

CMD ["php"]