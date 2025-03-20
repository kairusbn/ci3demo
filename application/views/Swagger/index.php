<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="description" content="iHOMIS+ API" />
        <title>iHOMIS+ API</title>
        <link
            rel="stylesheet"
            href="<?php echo base_url();?>assets/vendors/swagger/4.5.0/swagger-ui.css"
        />
    </head>
    <body>
        <div id="swagger-ui"></div>
        <script
            src="<?php echo base_url();?>assets/vendors/swagger/4.5.0/swagger-ui-bundle.js"
            crossorigin
        ></script>
        <script
            src="<?php echo base_url();?>assets/vendors/swagger/4.5.0/swagger-ui-standalone-preset.js"
            crossorigin
        ></script>
        <script>
            window.onload = () => {
                window.ui = SwaggerUIBundle({
                    url: "http://localhost/ci_project/openapi.json",
                    dom_id: "#swagger-ui",
                    presets: [
                        SwaggerUIBundle.presets.apis,
                        SwaggerUIStandalonePreset,
                    ],
                    layout: "StandaloneLayout",
                });
            };
        </script>
    </body>
</html>
