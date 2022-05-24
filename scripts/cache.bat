D:
cd D:\Projects\WhatsAppGroup\public\assets\js\cache
DEL /F/Q/S *.js > NUL
cd D:\Projects\WhatsAppGroup\public\assets\css\cache
DEL /F/Q/S *.css > NUL
cd D:\Projects\WhatsAppGroup\public
php index.php rest recache
@pause