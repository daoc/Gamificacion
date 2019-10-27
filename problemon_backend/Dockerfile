FROM node

RUN npm install -g loopback-cli

RUN mkdir /home/problemon

COPY data/ /home/problemon/

RUN cd /home/problemon && npm install loopback-connector-mongodb

CMD node /home/problemon

# Para crear la nueva imagen, ejecutar en la consola:
# docker build -t daoc/problemon_backend .

# Para arrancar el container, ejecutar en la consola:
# docker run -d -p 3000:3000 --name problemon_backend daoc/problemon_backend

