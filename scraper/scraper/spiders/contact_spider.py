import scrapy
import re
from urllib.parse import urlparse

EMAIL_REGEX = re.compile(
    r"[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
)

class ContactSpider(scrapy.Spider):
    name = "contacts"

    custom_settings = {
        "USER_AGENT": (
            "Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
            "AppleWebKit/537.36 (KHTML, like Gecko) "
            "Chrome/120.0.0.0 Safari/537.36"
        ),
        "ROBOTSTXT_OBEY": False,
        "LOG_LEVEL": "INFO",
    }

    def __init__(self, url=None, *args, **kwargs):
        super().__init__(*args, **kwargs)

        parsed = urlparse(url)
        self.start_urls = [url]

        # ✅ Scrapy attend une LISTE
        self.allowed_domains = [parsed.netloc]

        self.visited_urls = set()

    def parse(self, response):
        # ❌ évite les doublons
        if response.url in self.visited_urls:
            return
        self.visited_urls.add(response.url)

        page_title = response.css("title::text").get(default="")

        # ✅ EXTRACTION EMAIL PROPRE (TEXT ONLY)
        text_blocks = response.css(
            "body *:not(script):not(style)::text"
        ).getall()

        emails = set()
        for text in text_blocks:
            for match in EMAIL_REGEX.findall(text):
                emails.add(match)

        for email in emails:
            yield {
                "name": page_title.strip(),
                "email": email,
                "source_url": response.url,
            }

        # 🔗 Liens utiles uniquement
        for href in response.css("a::attr(href)").getall():
            if not href:
                continue

            url = response.urljoin(href)
            url_lower = url.lower()

            if (
                urlparse(url).netloc in self.allowed_domains
                and any(word in url_lower for word in [
                    "contact",
                    "about",
                    "a-propos",
                    "mentions",
                    "legal",
                    "impressum"
                ])
            ):
                yield scrapy.Request(url, callback=self.parse)
