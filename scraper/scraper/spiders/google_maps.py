import scrapy
import re

class GoogleMapsSpider(scrapy.Spider):
    name = "google_maps"

    def __init__(self, query=None, *args, **kwargs):
        super().__init__(*args, **kwargs)

        if not query:
            query = ""

        self.start_urls = [
            f"https://www.google.com/maps/search/{query.replace(' ', '+')}"
        ]

    def parse(self, response):

        for place in response.css('div.Nv2PK'):

            rating = place.css('span.MW4etd::text').get()
            reviews_raw = place.css('span.UY7F9::text').get()

            reviews = None
            if reviews_raw:
                reviews = re.search(r'\d+', reviews_raw.replace(" ", ""))
                reviews = reviews.group() if reviews else None

            yield {
                "name": place.css('a.hfpxzc::attr(aria-label)').get(),
                "category": place.css('div.W4Efsd span::text').get(),
                "address": place.css('div.W4Efsd div::text').get(),
                "phone": None,
                "website": None,
                "email": None,
                "rating": rating,
                "reviews_count": reviews,
            }
