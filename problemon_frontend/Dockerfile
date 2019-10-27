FROM webdevops/php-apache:alpine

RUN apk add php-curl

COPY data/ /app/

# Para crear la nueva imagen, ejecutar en la consola:
# docker build -t daoc/problemon_frontend .

# Para arrancar el container, ejecutar en la consola:
# docker run -d -p 8080:80 --name problemon_frontend daoc/problemon_frontend

#docker run --rm -it -p 8080:80 --name php -v /home/diego/docker/problemon_frontend/data:/app webdevops/php-apache:alpine
#docker run --rm -it -p 8080:80 --name php -v /home/diego/docker/problemon_frontend/data:/app problemon_frontend
