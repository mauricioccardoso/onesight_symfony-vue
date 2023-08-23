FROM node:18.16-slim

RUN apt update && apt install -y wget ncat

RUN wget -q -O /usr/bin/wait-for https://raw.githubusercontent.com/eficode/wait-for/v2.2.3/wait-for && \
    chmod +x /usr/bin/wait-for

USER node

WORKDIR /home/node/app

COPY ./.bash_aliases /home/node

EXPOSE 5173

CMD [ "/home/node/app/docker/start.sh" ]
