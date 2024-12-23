<footer class="footer py-2" id="contact">
    <div class="container">
        <p>Company Address: {{ $company->address ?? 'Address not available' }}</p>
        <p>Contact Us: {{ $company->email ?? 'Email not available' }}</p>
        <p>
            <a href="#">Privacy Policy</a> | 
            <a href="#">Terms of Service</a> | 
            <a href="#">Careers</a>
        </p>
    </div>
</footer>