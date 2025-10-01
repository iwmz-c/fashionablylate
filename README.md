# お問い合わせフォーム

## 環境構築
### Dockerビルド
1.git clone リンク  
2.docker-compose up -d --build

### Laravel環境構築
1.docker-compose exec php bash  
2.composer install  
3..env.exampleファイルから.envを作成し、環境変数を変更  
4.php artisan key:generate  
5.php artisan migrate  
6.php artisan db:seed  

## 使用技術(実行環境)
- PHP 8.1.33
- Laravel  8.83.8
- MySQL  11.8.3-MariaDB
- JavaScript

## ER図
<img width="775" height="554" alt="image" src="https://github.com/user-attachments/assets/0050e9a7-553f-4536-9072-f5f46d0fb81a" />

## URL
-  開発環境：http://localhost/
-  phpMyAdmin：http://localhost:8080/
