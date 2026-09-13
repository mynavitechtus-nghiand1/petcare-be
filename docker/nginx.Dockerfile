FROM nginx:1.29

# Copy Nginx base config and site configs
COPY docker/config/nginx.conf /etc/nginx/nginx.conf
COPY docker/config/conf.d/ /etc/nginx/conf.d/

# Expose default HTTP port
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]


