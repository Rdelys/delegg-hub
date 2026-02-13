import scrapy
import re
from urllib.parse import urlparse

EMAIL_REGEX = re.compile(r"[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}")

class ContactSpider(scrapy.Spider):
    name = "contacts"

    custom_settings = {
        "USER_AGENT": "Mozilla/5.0",
        "ROBOTSTXT_OBEY": False,
    }

    def __init__(self, url=None, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.start_urls = [url]
        self.allowed_domains = [urlparse(url).netloc]
        self.visited_urls = set()

    def parse(self, response):

        if response.url in self.visited_urls:
            return
        self.visited_urls.add(response.url)

        page_title = response.css("title::text").get(default="")

        text_blocks = response.css(
            "body *:not(script):not(style)::text"
        ).getall()

        emails = set()
        for text in text_blocks:
            for match in EMAIL_REGEX.findall(text):
                emails.add(match)

        # 🔥 EXTRACTION RÉSEAUX SOCIAUX
        facebook = None
        instagram = None
        linkedin = None

        for href in response.css("a::attr(href)").getall():
            if not href:
                continue

            url = response.urljoin(href).lower()

            if "facebook.com" in url and not facebook:
                facebook = url

            if "instagram.com" in url and not instagram:
                instagram = url

            if "linkedin.com" in url and not linkedin:
                linkedin = url

        for email in emails:
            yield {
                "name": page_title.strip(),
                "email": email,
                "source_url": response.url,
                "facebook": facebook,
                "instagram": instagram,
                "linkedin": linkedin,
            }

        # Pages contact uniquement
        for href in response.css("a::attr(href)").getall():
            url = response.urljoin(href)
            if urlparse(url).netloc in self.allowed_domains:
                if any(word in url.lower() for word in [
                    "contact", "about", "mentions", "legal"
                ]):
                    yield scrapy.Request(url, callback=self.parse)
