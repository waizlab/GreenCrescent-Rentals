<footer id="site-footer">
    <div class="footer-content">
        &copy; <?php echo date("Y"); ?> GreenCrescent Rentals. 
        Project created by students of Class 4B for DBMS, instructed by Sir Sagheer.
    </div>
</footer>

<style>
#site-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #2E2E2E;
    color: #F5FFFA;
    text-align: center;
    padding: 10px 0;
    font-family: 'Lato', sans-serif;
    font-size: 14px;
    transform: translateY(100%);
    transition: transform 0.3s ease;
    z-index: 100;
}
body:hover #site-footer,
#site-footer:hover {
    transform: translateY(0);
}
</style>
