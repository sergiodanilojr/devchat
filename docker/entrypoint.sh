#!/bin/bash

echo "Verificando permissões..."
# Testa se pode alterar, caso contrário, apenas continua
if chown -R resoluto:resoluto /var/www/html 2>/dev/null; then
  echo "Permissões ajustadas"
else
  echo "Sem permissão para alterar arquivos montados (volume), continuando mesmo assim..."
fi


# Verifica se o usuário existe no container
if id resoluto &>/dev/null; then
  echo "Iniciando como resoluto..."
  exec gosu resoluto "$@"
else
  echo "Usuário resoluto não encontrado no container. Iniciando como root..."
  exec "$@"
fi
