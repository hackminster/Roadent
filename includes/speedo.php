<svg width="200" height="200">
            
    <!-- dial face -->
    <circle cx="100" cy="100" r="80" stroke="#8585ad" stroke-width="4" fill="white" />
    
    <!-- markers -->
    <line x1="100" y1="35" x2="100" y2="25" style="stroke:rgb(255,0,0);stroke-width:2" />
    
    <line x1="100" y1="35" x2="100" y2="25" style="stroke:rgb(255,0,0);stroke-width:2"
    transform="rotate(-150,100,100)"/>
    <line x1="100" y1="35" x2="100" y2="25" style="stroke:rgb(255,0,0);stroke-width:2"
    transform="rotate(-120,100,100)"/>
    <line x1="100" y1="35" x2="100" y2="25" style="stroke:rgb(255,0,0);stroke-width:2"
    transform="rotate(-90,100,100)"/>
    <line x1="100" y1="35" x2="100" y2="25" style="stroke:rgb(255,0,0);stroke-width:2"
    transform="rotate(-60,100,100)"/>
    <line x1="100" y1="35" x2="100" y2="25" style="stroke:rgb(255,0,0);stroke-width:2"
    transform="rotate(-30,100,100)"/>
    <line x1="100" y1="35" x2="100" y2="25" style="stroke:rgb(255,0,0);stroke-width:2"
    transform="rotate(30,100,100)"/>
    <line x1="100" y1="35" x2="100" y2="25" style="stroke:rgb(255,0,0);stroke-width:2"
    transform="rotate(60,100,100)"/>
    <line x1="100" y1="35" x2="100" y2="25" style="stroke:rgb(255,0,0);stroke-width:2"
    transform="rotate(90,100,100)"/>
    <line x1="100" y1="35" x2="100" y2="25" style="stroke:rgb(255,0,0);stroke-width:2"
    transform="rotate(120,100,100)"/>
    <line x1="100" y1="35" x2="100" y2="25" style="stroke:rgb(255,0,0);stroke-width:2"
    transform="rotate(150,100,100)"/>

    <!-- unit -->
    <text x=100 y=135 text-anchor="middle" fill="red">MPH</text> 

    <!-- numerals -->
    <text x=72.5 y=152.6 text-anchor="middle">0</text> 
    <text x=52.4 y=132.5 text-anchor="middle">1</text>  
    <text x=45.0 y=105.0 text-anchor="middle">2</text>  
    <text x=52.4 y=77.5 text-anchor="middle">3</text>  
    <text x=72.5 y=57.4 text-anchor="middle">4</text>  
    <text x=100.0 y=50.0 text-anchor="middle">5</text>  
    <text x=127.5 y=57.4 text-anchor="middle">6</text>  
    <text x=147.6 y=77.5 text-anchor="middle">7</text>  
    <text x=155.0 y=105.0 text-anchor="middle">8</text>   
    <text x=147.6 y=132.5 text-anchor="middle">9</text>
    <text x=127.5 y=152.6 text-anchor="middle">10</text>  
    
    <!-- neddle -->
    <circle cx="100" cy="100" r="5" stroke="black" stroke-width="1" fill="black" />
    <polygon points="95,100 100,50 105,100" style="fill:black;stroke:black;stroke-width:1"
    transform="rotate(-60,100,100)" id="<?php echo $name; ?>"/>
            
</svg>