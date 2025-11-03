<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <title>403 Access denied - keys | #Codevember 22</title>
    <link rel="stylesheet" href="https://public.codepenassets.com/css/reset-2.0.min.css">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Lato:300,900'><link rel="stylesheet" href="{{ asset('css/403_style.css') }}">

  </head>
    
  <body>
  <svg class="svg-defs" xmlns="http://www.w3.org/2000/svg" version="1.1">
  <defs>
    <filter id="squiggly-0">
      <feTurbulence id="turbulence" baseFrequency="0.02" numOctaves="3" result="noise" seed="0"/>
      <feDisplacementMap id="displacement" in="SourceGraphic" in2="noise" scale="2" />
    </filter>
    <filter id="squiggly-1">
      <feTurbulence id="turbulence" baseFrequency="0.02" numOctaves="3" result="noise" seed="1"/>
<feDisplacementMap in="SourceGraphic" in2="noise" scale="3" />
    </filter>
    
    <filter id="squiggly-2">
      <feTurbulence id="turbulence" baseFrequency="0.02" numOctaves="3" result="noise" seed="2"/>
<feDisplacementMap in="SourceGraphic" in2="noise" scale="2" />
    </filter>
    <filter id="squiggly-3">
      <feTurbulence id="turbulence" baseFrequency="0.02" numOctaves="3" result="noise" seed="3"/>
<feDisplacementMap in="SourceGraphic" in2="noise" scale="3" />
    </filter>
    
    <filter id="squiggly-4">
      <feTurbulence id="turbulence" baseFrequency="0.02" numOctaves="3" result="noise" seed="4"/>
<feDisplacementMap in="SourceGraphic" in2="noise" scale="1" />
    </filter>
  </defs> 
</svg>

<section class="error">
	<div class="stage">
		<div class="key">
			<div class="key__head">
				<div class="key__eye"></div>
				<div class="key__eye"></div>
				<div class="key__mouth"></div>
			</div>
			<div class="key__body">
				<div class="key__indentation"></div>
				<div class="key__indentation"></div>
				<div class="key__indentation"></div>
				<div class="key__arm key__arm--left"></div>
				<div class="key__arm key__arm--right"></div>
			</div>
		</div>
		<div class="lock"></div>
	</div>
	<h1 class="error__header">403</h1>
	<h2 class="error__header error__header--secondary">Access denied</h2>
	<p class="error__description">Sorry, buddy, you really shouldn't be here.</p>
    {{-- Button to Home Page --}}
    <a href="{{ route('home') }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 5px;margin-top: 30px;">Go to Home Page</a>
</section>
    
  </body>
  
</html>
