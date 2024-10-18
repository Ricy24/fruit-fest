import pandas as pd
import mysql.connector

# Configura la conexión a tu base de datos
connection = mysql.connector.connect(
    host="localhost",       # Cambia esto si tu base de datos está en otro servidor
    user="root",       # Tu usuario de MySQL
    password="",# Tu contraseña de MySQL
    database="fruitfest" # Nombre de la base de datos
)

# Cargar los likes y los reels desde la base de datos
likes = pd.read_sql("SELECT * FROM likes", connection)
reels = pd.read_sql("SELECT * FROM reels", connection)

# Recomendar productos basados en los likes de un usuario
def recomendar_reels(user_id):
    # Filtrar los likes del usuario
    user_likes = likes[likes['user_id'] == user_id]
    
    # Obtener los reels que el usuario ha visto
    reels_vistos = user_likes['reel_id'].unique()

    # Obtener los reels más populares o similares que el usuario aún no ha visto
    recomendaciones = reels[~reels['id'].isin(reels_vistos)].sort_values(by='me_gustas', ascending=False)
    return recomendaciones.head(5)  # Retorna los 5 reels más recomendados

# Ejemplo de recomendación
recomendaciones = recomendar_reels(1)
print(recomendaciones)
