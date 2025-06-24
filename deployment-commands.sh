#!/bin/bash

# Step 1: Deploy the Kubernetes resources
echo "Deploying Kubernetes resources..."
kubectl apply -f kubernetes-deployment.yaml

# Step 2: Wait for pods to be ready
echo "Waiting for pods to be ready..."
kubectl wait --for=condition=ready pod -l app=laravel-app-ssh --timeout=300s

# Step 3: Get pod name
POD_NAME=$(kubectl get pods -l app=laravel-app-ssh -o jsonpath="{.items[0].metadata.name}")
echo "Pod name: $POD_NAME"

# Step 4: Check pod status
kubectl get pods -l app=laravel-app-ssh

# Step 5: Access web container and get PHP modules
echo "Getting PHP modules from web container..."
kubectl exec -it $POD_NAME -c web-server-ssh -- php -m > php-modules.txt

# Step 6: Access MySQL container and get tables
echo "Getting MySQL tables..."
kubectl exec -it $POD_NAME -c mysql-db -- mysql -u root -pHello@123 -e "USE \`yourname-db\`; SHOW TABLES;" > mysql-tables.txt

# Step 7: Display services
kubectl get services

# Step 8: Get node port information
echo "Access your application at:"
NODE_IP=$(kubectl get nodes -o jsonpath='{.items[0].status.addresses[?(@.type=="ExternalIP")].address}')
if [ -z "$NODE_IP" ]; then
    NODE_IP=$(kubectl get nodes -o jsonpath='{.items[0].status.addresses[?(@.type=="InternalIP")].address}')
fi
echo "Web: http://$NODE_IP:30080"
echo "SSH: ssh root@$NODE_IP -p 30022 (password: Hello@123)"