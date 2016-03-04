# note: this should never truly be refernced since we are using relative assets
sourcemap = true

add_import_path "../../rwd/default/scss"  
http_path = "/skin/frontend/default/sashasv2/"
css_dir = "css"
sass_dir = "scss"
images_dir = "images"
javascripts_dir = "js"
relative_assets = true
fonts_dir = "fonts"


output_style = :expanded
output_style = :compressed 

environment = :production


line_comments = false
cache = true
color_output = true # required for mixture

Sass::Script::Number.precision = 7 # chrome needs a precision of 7 to round properly