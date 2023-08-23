FROM docker.io/bitnami/symfony:6.3

RUN apt update && apt install -y wget ncat git

RUN wget -q -O /usr/bin/wait-for https://raw.githubusercontent.com/eficode/wait-for/v2.2.3/wait-for && \
    chmod +x /usr/bin/wait-for

RUN wget https://get.symfony.com/cli/installer -O - | bash && \
    mv /root/.symfony5/bin/symfony /usr/local/bin/symfony

USER bitnami

WORKDIR /home/bitnami/backend

COPY .bash_aliases /home/bitnami/

EXPOSE 8000

CMD [ "/home/bitnami/backend/docker/start.sh" ]
