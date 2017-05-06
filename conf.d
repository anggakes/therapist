[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php ~/code/therapist/artisan queue:work sqs --sleep=3 --tries=3 --daemon
autostart=true
autorestart=true
user=vagrant
numprocs=8
redirect_stderr=true
stdout_logfile=~/code/therapist/worker.log
