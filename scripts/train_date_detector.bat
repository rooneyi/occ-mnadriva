@echo off
setlocal enabledelayedexpansion

echo Installation de l'environnement YOLOv5...

:: Vérifier si Python est installé
python --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo Erreur: Python n'est pas installé ou n'est pas dans le PATH.
    echo Veuillez installer Python 3.8 ou supérieur depuis https://www.python.org/downloads/
    pause
    exit /b 1
)

:: Vérifier si Git est installé
git --version >nul 2>&1
if %ERRORLEVEL% neq 0 (
    echo Erreur: Git n'est pas installé ou n'est pas dans le PATH.
    echo Veuillez installer Git depuis https://git-scm.com/download/win
    pause
    exit /b 1
)

:: Créer un environnement virtuel
echo Création de l'environnement virtuel...
python -m venv venv
call venv\Scripts\activate

:: Installer les dépendances
echo Installation des dépendances...
pip install --upgrade pip
pip install torch torchvision torchaudio --index-url https://download.pytorch.org/whl/cu118  # CUDA 11.8
pip install -r requirements.txt

:: Cloner YOLOv5 si ce n'est pas déjà fait
if not exist "yolov5" (
    echo Téléchargement de YOLOv5...
    git clone https://github.com/ultralytics/yolov5
    cd yolov5
    pip install -r requirements.txt
    cd ..
)

:: Vérifier si un GPU est disponible
echo Vérification de la disponibilité du GPU...
python -c "import torch; print(f'CUDA disponible: {torch.cuda.is_available()}')"

:: Lancer l'entraînement
echo.
echo =======================================
echo DÉMARRAGE DE L'ENTRAÎNEMENT
echo =======================================
echo.

echo Paramètres:
echo - Dossier d'entrée: %cd%\dataset
echo - Dossier de sortie: %cd%\yolov5\data\date_detection
echo - Nombre d'époques: 50
echo - Taille du lot: 16
echo - Taille d'image: 640
echo.

python scripts/train_date_detector.py --input-dir "%cd%\dataset" --output-dir "%cd%\yolov5\data\date_detection" --epochs 50 --batch-size 16 --img-size 640

if %ERRORLEVEL% equ 0 (
    echo.
    echo =======================================
    echo ENTRAÎNEMENT TERMINÉ AVEC SUCCÈS!
    echo =======================================
    echo.
    
    :: Copier le modèle entraîné
    if not exist "storage\app\models\yolo" mkdir "storage\app\models\yolo"
    copy /Y "runs\train\date_detection\weights\best.pt" "storage\app\models\yolo\date_detection.pt"
    
    echo Modèle copié vers: %cd%\storage\app\models\yolo\date_detection.pt
    echo.
    echo Pour utiliser le modèle dans votre application Laravel, assurez-vous que le chemin du modèle dans YoloService.php est correct:
    echo.
    echo protected $modelPath = storage_path(''app/models/yolo/date_detection.pt'');
    echo.
) else (
    echo.
    echo =======================================
    echo ERREUR LORS DE L'ENTRAÎNEMENT
    echo =======================================
    echo.
)

pause
