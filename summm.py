import json
import os

# Список файлов для объединения (без расширения .json)
files_to_merge = [
    "Baguette",
    "Gorizont ALU",
    "Gorizont",
    "Kvalitet",
    "S",
    "SP",
    "Zadoor-S"
]

# Создаем словарь для объединенных данных
merged_data = {}

# Читаем каждый файл и добавляем его содержимое в словарь
for filename in files_to_merge:
    filepath = f"{filename}.json"
    if os.path.exists(filepath):
        with open(filepath, 'r', encoding='utf-8') as f:
            try:
                data = json.load(f)
                merged_data[filename] = data
            except json.JSONDecodeError as e:
                print(f"Ошибка при чтении файла {filename}.json: {e}")
    else:
        print(f"Файл {filename}.json не найден")

# Сохраняем объединенные данные в zador.json
with open('sum.json', 'w', encoding='utf-8') as f:
    json.dump(merged_data, f, ensure_ascii=False, indent=4)

print("Объединение завершено. Результат сохранен в sum.json")
