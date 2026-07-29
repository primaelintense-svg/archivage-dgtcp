#!/usr/bin/env bash
 
echo "Génération de la clé d'application si nécessaire..."
php artisan key:generate --force --show || true
 
echo "Exécution des migrations..."
php artisan migrate --force
 
echo "Mise en cache de la configuration..."
php artisan config:cache
 
echo "Mise en cache des routes..."
php artisan route:cache
 
echo "Mise en cache des vues..."
php artisan view:cache
 
echo "Lien symbolique de stockage..."
php artisan storage:link || true
 