#!/bin/bash
# Regenera la rama `deploy-web`: el contenido de web/ como raíz (artisan, public/, composer.json…).
# El servidor trae esa rama, así `httpdocs/` queda con la app directamente y el document root es `httpdocs/public`.
# Deja afuera, por construcción, la carpeta hierro-metal/ (plan, legajo, presupuesto).
#
# Sólo prepara la rama en local. NO hace push: el push se pide y se confirma aparte.
set -euo pipefail

cd "$(git rev-parse --show-toplevel)"

[ -z "$(git status --porcelain)" ] || { echo "Hay cambios sin commitear: commitealos antes de preparar el deploy." >&2; exit 1; }

ORIGEN="${1:-main}"
sha=$(git subtree split --prefix=web "$ORIGEN" 2>/dev/null | tail -1)   # determinista: el mismo contenido da el mismo hash
git branch -f deploy-web "$sha"

echo "deploy-web -> $(git log --oneline -1 deploy-web)"
echo "Para publicarla (pedir OK antes):  git push origin deploy-web"
