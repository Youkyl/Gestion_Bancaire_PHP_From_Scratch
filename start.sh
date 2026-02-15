#!/bin/bash

# Script de démarrage pour Render
echo "Démarrage du serveur PHP sur le port $PORT..."

# Démarrer le serveur PHP
php -S 0.0.0.0:${PORT:-8000} -t public
