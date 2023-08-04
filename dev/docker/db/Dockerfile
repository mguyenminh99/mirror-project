FROM mysql:5.7

ARG UID
ARG GID

RUN groupadd -g $GID x-shopping-st && useradd -r -m -u $UID -g $GID x-shopping-st

USER x-shopping-st