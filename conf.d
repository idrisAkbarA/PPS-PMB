[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/pmb-pasca/public_html/pmb-pasca/artisan queue:work --tries=3 --sleep=3
user=root
autostart=true
autorestart=true
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/html/shooping_add_web_application/storage/logs/queue.log