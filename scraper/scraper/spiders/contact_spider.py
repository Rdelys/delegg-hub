import scrapy
import re
from urllib.parse import urlparse

EMAIL_REGEX = r"[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+"

class ContactSpider(scrapy.Spider):
    name = "contacts"

    def __init__(self, url=None, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.start_urls = [url]
        self.allowed_domain = urlparse(url).netloc

    def parse(self, response):
        # 1️⃣ Cherche emails sur la page
        emails = set(re.findall(EMAIL_REGEX, response.text))

        page_title = response.css("title::text").get()

        for email in emails:
            yield {
                "name": page_title,
                "email": email,
                "source_url": response.url
            }

        # 2️⃣ Suit les liens intéressants (contact, about, mentions)
        for link in response.css("a::attr(href)").getall():
            if link:
                link = response.urljoin(link)

                if self.allowed_domain in link and any(
                    word in link.lower()
                    for word in ["contact", "about", "mentions", "legal", "impressum"]
                ):
                    yield scrapy.Request(link, callback=self.parse)
