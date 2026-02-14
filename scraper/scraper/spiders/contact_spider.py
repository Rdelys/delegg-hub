import scrapy
import re
from urllib.parse import urlparse
import time

EMAIL_REGEX = re.compile(r"[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}")

class ContactSpider(scrapy.Spider):
    name = "contacts"

    custom_settings = {
        "USER_AGENT": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
        "ROBOTSTXT_OBEY": False,
        "DOWNLOAD_DELAY": 1,
        "CONCURRENT_REQUESTS": 1,
        "HTTPCACHE_ENABLED": False,  # Désactiver le cache HTTP
        "COOKIES_ENABLED": False,     # Désactiver les cookies
        "RETRY_TIMES": 2,
        "RETRY_HTTP_CODES": [500, 502, 503, 504, 400, 403, 404, 408],
        "DOWNLOAD_TIMEOUT": 30,
        "USER_AGENT_LIST": [
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
            "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36",
            "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36"
        ]
    }

    def __init__(self, url=None, *args, **kwargs):
        super().__init__(*args, **kwargs)
        
        # Nettoyer et valider l'URL
        if url:
            url = url.strip()
            # Enlever le timestamp si présent
            url = re.sub(r'[?&]_t=\d+', '', url)
            
            if not url.startswith(('http://', 'https://')):
                url = 'https://' + url
        
        self.start_urls = [url] if url else []
        
        if url:
            self.allowed_domains = [urlparse(url).netloc]
        else:
            self.allowed_domains = []
            
        self.visited_urls = set()
        
        # Log pour debug
        self.logger.info(f"🚀 Spider démarré avec URL: {url}")
        self.logger.info(f"🌐 Domaine autorisé: {self.allowed_domains}")

    def start_requests(self):
        """Surcharger pour forcer une nouvelle requête"""
        for url in self.start_urls:
            yield scrapy.Request(
                url, 
                callback=self.parse,
                dont_filter=True,  # Ne pas filtrer les URLs déjà vues
                headers={
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0',
                    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                },
                meta={
                    'dont_merge_cookies': True,
                    'dont_cache': True
                }
            )

    def parse(self, response):
        # Vérifier que l'URL correspond à celle demandée
        expected_url = self.start_urls[0] if self.start_urls else None
        
        if response.url in self.visited_urls:
            return
            
        self.visited_urls.add(response.url)
        self.logger.info(f"✅ Parsing: {response.url}")

        # Extraire le titre
        page_title = response.css("title::text").get(default="")
        page_title = page_title.strip() if page_title else ""

        # Extraire tout le texte
        text_blocks = response.css(
            "body *:not(script):not(style)::text"
        ).getall()

        # Extraire les emails
        emails = set()
        for text in text_blocks:
            for match in EMAIL_REGEX.findall(text):
                emails.add(match.lower())  # Convertir en minuscules

        self.logger.info(f"📧 Emails trouvés: {len(emails)}")

        # Extraire les réseaux sociaux
        facebook = None
        instagram = None
        linkedin = None

        for href in response.css("a::attr(href)").getall():
            if not href:
                continue

            url = response.urljoin(href).lower()

            if "facebook.com" in url and not facebook and "share" not in url:
                facebook = url

            if "instagram.com" in url and not instagram:
                instagram = url

            if "linkedin.com" in url and not linkedin and "share" not in url:
                linkedin = url

        # Générer les résultats
        for email in emails:
            yield {
                "name": page_title,
                "email": email,
                "source_url": response.url,
                "facebook": facebook,
                "instagram": instagram,
                "linkedin": linkedin,
            }

        # Trouver et suivre les pages de contact
        contact_keywords = ["contact", "about", "mentions", "legal", "a-propos", "qui-sommes-nous"]
        
        for href in response.css("a::attr(href)").getall():
            if not href:
                continue
                
            url = response.urljoin(href)
            
            # Vérifier si c'est dans le même domaine
            if urlparse(url).netloc in self.allowed_domains:
                # Vérifier si c'est une page de contact
                if any(keyword in url.lower() for keyword in contact_keywords):
                    if url not in self.visited_urls:
                        self.logger.info(f"🔍 Page de contact trouvée: {url}")
                        yield scrapy.Request(
                            url, 
                            callback=self.parse,
                            dont_filter=True,
                            headers={
                                'Cache-Control': 'no-cache',
                                'Pragma': 'no-cache'
                            }
                        )