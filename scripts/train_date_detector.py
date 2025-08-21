import os
import shutil
import yaml
from pathlib import Path
import random
import argparse

def prepare_dataset(input_dir, output_dir, train_ratio=0.8):
    """
    Prépare le dataset pour YOLO en créant les dossiers nécessaires
    et en divisant les données en ensembles d'entraînement et de validation.
    """
    # Chemins des dossiers
    images_dir = Path(input_dir) / 'images'
    labels_dir = Path(input_dir) / 'labels'
    
    # Créer la structure de dossiers YOLO
    yolo_dirs = {
        'train': {
            'images': Path(output_dir) / 'images' / 'train',
            'labels': Path(output_dir) / 'labels' / 'train'
        },
        'val': {
            'images': Path(output_dir) / 'images' / 'val',
            'labels': Path(output_dir) / 'labels' / 'val'
        }
    }
    
    # Créer les dossiers
    for split in yolo_dirs.values():
        for d in split.values():
            d.mkdir(parents=True, exist_ok=True)
    
    # Lister tous les fichiers d'images
    image_files = list(images_dir.glob('*.jpg')) + list(images_dir.glob('*.png'))
    random.shuffle(image_files)
    
    # Diviser en ensembles d'entraînement et de validation
    split_idx = int(len(image_files) * train_ratio)
    train_files = image_files[:split_idx]
    val_files = image_files[split_idx:]
    
    print(f"Images totales: {len(image_files)}")
    print(f"  - Entraînement: {len(train_files)}")
    print(f"  - Validation: {len(val_files)}")
    
    # Copier les fichiers dans la structure YOLO
    def copy_files(files, split):
        for img_path in files:
            # Copier l'image
            dest_img = yolo_dirs[split]['images'] / img_path.name
            shutil.copy2(img_path, dest_img)
            
            # Copier le fichier d'étiquettes correspondant
            label_path = labels_dir / f"{img_path.stem}.txt"
            if label_path.exists():
                dest_label = yolo_dirs[split]['labels'] / f"{img_path.stem}.txt"
                shutil.copy2(label_path, dest_label)
    
    copy_files(train_files, 'train')
    copy_files(val_files, 'val')
    
    return str(Path(output_dir).resolve())

def create_yaml_config(data_dir, output_path):
    """Crée le fichier de configuration YAML pour YOLOv5."""
    config = {
        'train': str(Path(data_dir) / 'images' / 'train'),
        'val': str(Path(data_dir) / 'images' / 'val'),
        'nc': 1,  # Nombre de classes
        'names': ['date']  # Noms des classes
    }
    
    with open(output_path, 'w') as f:
        yaml.dump(config, f, default_flow_style=False)
    
    return output_path

def train_model(data_yaml, epochs=50, batch_size=16, img_size=640, weights='yolov5s.pt'):
    """Lance l'entraînement du modèle YOLOv5."""
    import torch
    from yolov5 import train
    
    # Vérifier si CUDA est disponible
    device = 'cuda' if torch.cuda.is_available() else 'cpu'
    print(f"Utilisation de {device.upper()} pour l'entraînement")
    
    # Paramètres d'entraînement
    train.run(
        data=data_yaml,
        weights=weights,
        epochs=epochs,
        batch_size=batch_size,
        img_size=img_size,
        device=device,
        project='runs/train',
        name='date_detection',
        exist_ok=True
    )

def main():
    parser = argparse.ArgumentParser(description='Entraînement du détecteur de dates YOLOv5')
    parser.add_argument('--input-dir', type=str, required=True, help='Dossier contenant les images et labels')
    parser.add_argument('--output-dir', type=str, default='yolov5/data/date_detection', help='Dossier de sortie pour le dataset préparé')
    parser.add_argument('--epochs', type=int, default=50, help="Nombre d'époques d'entraînement")
    parser.add_argument('--batch-size', type=int, default=16, help="Taille du lot d'entraînement")
    parser.add_argument('--img-size', type=int, default=640, help="Taille d'image pour l'entraînement")
    
    args = parser.parse_args()
    
    # 1. Préparer le dataset
    print("Préparation du dataset...")
    data_dir = prepare_dataset(args.input_dir, args.output_dir)
    
    # 2. Créer le fichier de configuration
    print("\nCréation du fichier de configuration...")
    yaml_path = Path(data_dir) / 'date_detection.yaml'
    create_yaml_config(data_dir, yaml_path)
    
    # 3. Lancer l'entraînement
    print("\nDémarrage de l'entraînement...")
    train_model(
        data_yaml=str(yaml_path),
        epochs=args.epochs,
        batch_size=args.batch_size,
        img_size=args.img_size
    )
    
    print("\nEntraînement terminé avec succès!")
    print(f"Modèle enregistré dans: runs/train/date_detection/weights/best.pt")

if __name__ == "__main__":
    main()
