<!-- resources/views/homepage.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{URL::asset('/images/logo-circle.png')}}">
    <!--Css-->
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Work+Sans:300,400,500,700,800|Poppins:300,400,700">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" id="main-styles-link">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #fdf7f4;
            color: white;
        }
        .navbar {
            position: fixed; /* Fixed navbar */
            width: 100%; 
            z-index: 1000; /* Ensure it stays on top */
        }
        .hero {
            background-image: url('{{ asset('assets/images/background.png') }}');
            background-size: cover;          /* Ensure the image covers the section */
            padding: 130px 0;
            font-weight: bold;
            text-align: center;
            position: relative;              /* This ensures we can add an overlay if needed */
            color: white;     
            overflow: hidden;               

        }
        /* Add a pseudo-element for the blur effect */
.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('{{ asset('assets/images/background.png') }}') no-repeat center center; 
    background-size: cover;
    filter: blur(2px); /* Apply the blur effect */
    z-index: 1;  /* Make sure the blurred background is below the text */
}

/* Dark overlay to ensure readability */
.hero::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);  /* Dark semi-transparent overlay */
    z-index: 2;  /* Place the overlay above the blurred background */
}

        .hero h1 {
            position: relative;
            z-index: 3;
            font-size: 2.5rem;
            margin-bottom: 20px;             
        }

        .hero p {
            position: relative;
            z-index: 3;
            font-size: 1.2rem;
            margin-bottom: 30px;             
        }
        .company-values {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 10px;
            color: black;
        }
        .card {
            transition: transform 0.3s;
            background-color: #f8f9fa;
            color: black;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .section-title {
            color: black;
        }
        .testimonial {
            background: white;
            padding: 20px;
            border-radius: 10px;
            color: black;
        }
        .apply-job-btn {
            position: relative;
            z-index: 3;
            color: white;
            background-color: black;
        }
        .apply-job-btn:hover {
            background-color: black;
            color: white;
        }
        .testimonial-card {
            background-color: white;
            transition: box-shadow 0.3s;
        }
        .testimonial-card:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
    </style>
    <title>Homepage - HR Tech</title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="{{ route('homepage') }}">HRTech Inc.</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('homepage') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#about">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="#services">Our Services</a></li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero">
        <h1>Welcome to HRTech Inc.</h1>
        <p>Join us and be a part of the future! We are looking for talented team members.</p>
        <a href="{{ route('form/job/list') }}" class="btn apply-job-btn btn-lg">Apply Now</a>
    </header>

    <main class="container my-5">
        <!-- Why Join Us -->
        <section class="company-values mb-5" id="about">
            <h2 class="mb-4 section-title">Why Join Us</h2>
            <p>At our company, we value innovation, collaboration, and diversity. Our mission is to create a supportive environment where every team member can thrive.</p>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-gift fa-2x mb-2"></i>
                            <h5 class="card-title">Company Benefits</h5>
                            <p class="card-text">Health Insurance, Flexible Hours, Professional Development.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x mb-2"></i>
                            <h5 class="card-title">Team Culture</h5>
                            <p class="card-text">We foster collaboration, innovation, and growth opportunities.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-comments fa-2x mb-2"></i>
                            <h5 class="card-title">Customer Testimonials</h5>
                            <p class="card-text">Hear from our clients about their experiences with us.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Customer Testimonials Section -->
        <section class="mb-5" id="services">
            <h2 class="mb-4 section-title">What Our Customers Say</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card testimonial-card">
                        <div class="card-body text-center">
                            <p class="card-text">"The service provided by this company has significantly improved our HR processes!"</p>
                            <footer class="blockquote-footer">Anonymous, <cite title="Source Title">HR Manager</cite></footer>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card testimonial-card">
                        <div class="card-body text-center">
                            <p class="card-text">"Their technology solutions have made a real difference in our operations!"</p>
                            <footer class="blockquote-footer">Anonymous, <cite title="Source Title">CEO of Local Business</cite></footer>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card testimonial-card">
                        <div class="card-body text-center">
                            <p class="card-text">"Excellent support and a great team to work with!"</p>
                            <footer class="blockquote-footer">Anonymous, <cite title="Source Title">Business Owner</cite></footer>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('layouts.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>