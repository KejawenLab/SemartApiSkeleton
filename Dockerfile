FROM ubuntu:20.04

MAINTAINER Muhammad Surya Ihsanuddin<surya.iksanudin@gmail.com>

ENV DEBIAN_FRONTEND noninteractive

# Install Software
RUN apt update && apt upgrade -y && apt autoremove -y
RUN apt install software-properties-common locales -y
RUN locale-gen en_US.UTF-8 && export LANG=en_US.UTF-8
RUN add-apt-repository ppa:ondrej/php -y
RUN apt install supervisor vim wget curl unzip -y
RUN apt install php8.0 php8.0-cli php8.0-curl php8.0-intl php8.0-mbstring php8.0-xml php8.0-zip php8.0-dev \
    php8.0-bcmath php8.0-cli php8.0-imap php8.0-opcache php8.0-xmlrpc php-pear \
    php8.0-bz2 php8.0-common php8.0-gd php8.0-ldap php8.0-mysql php8.0-readline php8.0-soap php8.0-tidy php8.0-xsl php8.0-redis -y

# Install Composer
ADD docker/php/composer.sh /composer.sh
RUN chmod a+x /composer.sh
RUN /composer.sh && mv composer.phar /usr/local/bin/composer && chmod a+x /usr/local/bin/composer
RUN rm -f /composer.sh

# Cleaning
RUN apt autoremove -y && apt clean && apt autoclean
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* ~/.composer

# Configuring
RUN echo "y\ny\ny\ny\ny\ny\n"| pecl install swoole
ADD docker/php/php.ini /etc/php/8.0/cli/php.ini
ADD docker/supervisor/supervisord.conf /etc/supervisord.conf

# Here we go
ADD docker/start.sh /start.sh
RUN chmod +x /start.sh

WORKDIR /semart

EXPOSE 9501

CMD ["/start.sh"]
