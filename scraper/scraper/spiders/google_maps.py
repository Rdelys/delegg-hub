import scrapy
import re

class GoogleMapsSpider(scrapy.Spider):
    name = "google_maps"

    def __init__(self, query=None, *args, **kwargs):
        super().__init__(*args, **kwargs)
        self.start_urls = [
            f"https://www.google.com/maps/search/{query.replace(' ', '+')}"
        ]

    def parse(self, response):
        for place in response.css('div.Nv2PK'):
            yield {
                "name": place.css('a.hfpxzc::attr(aria-label)').get(),
                "category": place.css('div.W4Efsd span::text').get(),
                "address": place.css('div.W4Efsd div::text').get(),
                "phone": None,
                "website": None,
                "email": None,
            }
