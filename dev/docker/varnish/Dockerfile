FROM varnish:6.3

ARG HOST_NAME

COPY ./dev/docker/varnish/default.vcl.template /etc/varnish/default.vcl

RUN sed -i "s/    .host = \"example.x-shopping-st.com\";/    .host = \"${HOST_NAME}\";/" /etc/varnish/default.vcl

RUN chown -R varnish:varnish /var/lib/ && chown varnish:varnish /etc/varnish/default.vcl

RUN echo "deb http://archive.debian.org/debian stretch main" > /etc/apt/sources.list && apt update

USER varnish
