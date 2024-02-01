load("ext://docker_build_sub", "docker_build_sub")
# Allow certain kubernetes contexts to deploy to and avoid the "accidental prod deploy"
allow_k8s_contexts('kind-kind')

docker_build_sub('cipherguard-ce', '../cipherguard_docker', child_context='.',
    extra_cmds=['COPY . /var/www/cipherguard',
                'RUN apt-get update && apt-get install -y git unzip \
                      && EXPECTED_SIGNATURE=$(curl -s https://composer.github.io/installer.sig) \
                      && curl -o composer-setup.php https://getcomposer.org/installer \
                      && php composer-setup.php --1 \
                      && mv composer.phar /usr/local/bin/composer \
                      && composer install -n \
                      && chown -R www-data:www-data vendor'
                ],
    live_update=[
        sync('.', '/var/www/cipherguard'),
        run('cd /var/www/cipherguard && composer install -n && chown -R www-data:www-data vendor', trigger=['./composer.json'])
        ])
# Helm chart path
path = '../../charts/charts-cipherguard'
watch_file(path)
watch_file('../cipherguard_docker')
yaml = helm(
  path,
  name = 'cipherguard-ce',
  namespace = 'on-prem',
  values = ["{}/values-local-ce.yaml".format(path)],
  )
k8s_yaml(yaml)

k8s_resource('cipherguard-ce-job-enable-selenium', resource_deps=['cipherguard-ce-depl-srv'])
k8s_resource('cipherguard-ce-depl-srv', resource_deps=['cipherguard-ce-job-init-databases'])
k8s_resource('cipherguard-ce-job-init-databases', resource_deps=['mariadb']) 
