pipeline {
    agent any 
    environment {
        DOCKERHUB_CREDENTIALS = credentials('docker')
    }
    stages {
        stage('Testing') { 
            steps {
                script {
                    last_started = env.STAGE_NAME
                }
                script {
                    sh '''#!/bin/bash
                        echo "start testing with phpUnit...";
                        cd src;
                        result=$(php artisan test);
                        # Get the exit code of the command
                        exit_code=$?;
                        # Check the exit code to determine if the tests passed or failed
                        if [ $exit_code -eq 0 ]; then
                            echo "All tests passed";
                        else
                            echo "Tests failed";
                            echo "$result";
                            exit 1;
                        fi
                        cd ..;
                    '''
                }
            }
        }
        stage('Build with Docker') { 
            steps {
                script {
                    last_started = env.STAGE_NAME
                }
                sh '''#!/bin/bash
                    echo "start building Docker images.....";
                    if echo $DOCKERHUB_CREDENTIALS_PSW | docker login -u $DOCKERHUB_CREDENTIALS_USR --password-stdin;then

                        # php image
                        echo "start building php image...";
                        if docker build . -t muhanedyahya/onlinecelebration-php:rds -f dockerfiles/php.dockerfile;then
                            echo "php image successfully created.";
                            echo "pushing php image to DockerHub.....";
                                if docker push muhanedyahya/onlinecelebration-php:rds;then
                                    echo "php image pushed seccessfully.";
                                else
                                    echo "error error occurred while pushing php image!!! something went wrong";exit 1;
                                fi
                        else
                            sh 'echo error occurred while building php the image';exit 1;
                        fi

                        # nginx image
                        echo "start building nginx image...";
                        if docker build . -t muhanedyahya/onlinecelebration-nginx:secret -f dockerfiles/nginx.dockerfile;then
                            echo "nginx image successfully created.";
                            echo "pushing nginx image to DockerHub.....";
                                if docker push muhanedyahya/onlinecelebration-nginx:secret;then
                                    echo "nginx image pushed seccessfully.";
                                else
                                    echo "error error occurred while pushing nginx image!!! something went wrong";exit 1;
                                fi
                        else
                            sh 'echo error occurred while building nginx the image';exit;
                        fi

                    else 
                        echo "cant login to docker hub!!!";exit 1;
                    fi    
                ''' 
            }
        }
        stage('Deploy on Kubernetes') { 
            steps {
                script {
                    last_started = env.STAGE_NAME
                }
                withKubeConfig([credentialsId: 'DOKS']) {
                    sh '''#!/bin/bash
                        echo "Checking Docker secret if exists so we can pull private images..";
                        result=$(kubectl get secrets | grep docker-secret);
                        if [ -z "$result" ]; then
                            echo "Docker Secret not found !!!";exit 1;
                        else
                            echo "Docker Secret exists...";
                        fi
                        echo "----------------------------------------------------------------------";
                        echo "Checking Application Secret and Configmaps if exists...";                        
                        result=$(kubectl get secrets | grep laravel-secret);
                        if [ -z "$result" ]; then
                            echo "Application Secret not found !!!";
                            echo "please make sure you have the required secret before running the project.";exit 1;
                        else
                            echo "the Application has a Secret.";
                        fi

                        result=$(kubectl get configmaps | grep laravel-configmap);
                        if [ -z "$result" ]; then
                            echo "Application configmap not found !!!";
                            echo "please make sure you have the required configmap before running the project.";exit 1;
                        else
                            echo "the application has a Configmap.";
                        fi

                        echo "----------------------------------------------------------------------";
                        echo "Checking Services if exists....";
                        if kubectl get service php -n default &> /dev/null; then
                            echo "Service 'php' exists.";
                        else
                            echo "Service 'php' does not exist. We will expose the service now ...";
                            kubectl apply -f kubernetes/php-service.yaml;
                        fi

                        if kubectl get service server -n default &> /dev/null; then
                            echo "Service 'server' exists.";
                        else
                            echo "Service 'server' does not exist. We will expose the service now ...";
                            kubectl apply -f kubernetes/nginx-service.yaml;
                        fi    

                        echo "----------------------------------------------------------------------";
                        echo "Running the app in kubernetes...";
                        if
                            status=$(kubectl get deployment php-deployment -o jsonpath='{.status.conditions[?(@.type=="Available")].status}' 2>/dev/null)
                            if [ "$status" == "True" ]; then
                                echo "php pod restarting";
                                kubectl rollout restart deployment/php-deployment;
                            else
                                kubectl apply -f kubernetes/php.yaml;
                            fi

                            status=$(kubectl get deployment nginx-deployment -o jsonpath='{.status.conditions[?(@.type=="Available")].status}' 2>/dev/null)
                            if [ "$status" == "True" ]; then
                                echo "php pod restarting";
                                kubectl rollout restart deployment/nginx-deployment;
                            else
                                kubectl apply -f kubernetes/nginx.yaml;
                                echo "----------------------------------------------------------------------";
                                // to avoid database cached connection
                                echo "running php artisan config:cache inside of php pod";
                                pod_name=$(kubectl get pods -l app=onlinecelebration-php -o jsonpath='{.items[0].metadata.name}');
                                kubectl exec $pod_name -- php artisan config:cache;
                            fi
                        then 
                            echo "Application deployed seccessfully on Kubernetes.";
                        else
                            echo "Error in deploying the application on kubernetes";exit 1;
                        fi
                    '''
                }
            }
        }
        stage('Monitoring') { 
            steps {
                script {
                    last_started = env.STAGE_NAME
                }
                withKubeConfig([credentialsId: 'DOKS']) {
                    sh '''#!/bin/bash
                        echo "Checking Monitoring Secret and Configmaps if exists...";                        
                        result=$(kubectl get configmaps | grep prometheus-config)
                        if [ -z "$result" ]; then
                            echo "Prometheus Configmap not found !!!";exit 1;
                        else
                            echo "Prometheus Configmap exists.";
                        fi

                        result=$(kubectl get secrets | grep grafana-secret)
                        if [ -z "$result" ]; then
                            echo "Grafana Secret not found !!!";exit 1;
                        else
                            echo "Grafana Secret exists.";
                        fi

                        echo "----------------------------------------------------------------------";
                        echo "Checking Monitoring Services if exists....";
                        if kubectl get service prometheus-service -n default &> /dev/null; then
                            echo "Prometheus Service exists.";
                        else
                            echo "Prometheus Service does not exist. We will expose the service now ...";
                            kubectl apply -f kubernetes/prometheus-service.yaml;
                        fi

                        if kubectl get service grafana-service -n default &> /dev/null; then
                            echo "Grafana Service exists.";
                        else
                            echo "Grafana Service does not exist. We will expose the service now ...";
                            kubectl apply -f kubernetes/grafana-service.yaml;
                        fi    

                        echo "----------------------------------------------------------------------";
                        echo "Checking Monitoring tools if exists....";
                        if
                            status=$(kubectl get deployment prometheus -o jsonpath='{.status.conditions[?(@.type=="Available")].status}' 2>/dev/null)
                            if [ "$status" == "True" ]; then
                                echo "Prometheus already exists.";
                            else
                                echo "Prometheus deployment not found. We will apply the deployment now ...";
                                kubectl apply -f kubernetes/prometheus.yaml;
                                echo "----------------------------------------------------------------------";

                                status=$(kubectl get deployment grafana -o jsonpath='{.status.conditions[?(@.type=="Available")].status}' 2>/dev/null)
                                if [ "$status" == "True" ]; then
                                    echo "grafana already exists.";
                                else
                                    echo "Prometheus deployment not found. We will apply the deployment now ...";
                                    kubectl apply -f kubernetes/grafana.yaml;
                                    echo "----------------------------------------------------------------------";
                                fi
                            fi
                        then 
                            echo "Monitoring tools deployed seccessfully on Kubernetes.";
                        else
                            echo "Error in the Monitoring tools";exit 1;
                        fi
                    '''
            }   }
        }
    }

    post{  
         success {  
             mail(body: 'All stages have been successfully prepared and deployed...', subject: 'Application successfully deployed :)', to: 'mohannad11bale@gmail.com')
         }  
         failure {  
            mail(body: "An error occurred during the $last_started stage", subject: "$last_started stage Alert !!!", to: 'mohannad11bale@gmail.com') 
         } 

    }  
}