
class offer{
  int entity_id;
  String offer_title;
  String offer_discription;
  String offer_detalis;
  String offer_price;
  String offer_discount;
  String is_discount;
  String offer_photo;

  offer({
    this.entity_id,
      this.offer_title,
      this.offer_discription,
      this.offer_detalis,
      this.offer_price,
      this.offer_discount,
      this.is_discount,
      this.offer_photo
  });
  factory offer.fromJson(Map<String, dynamic> paresdJson){
    return offer(
        offer_title: paresdJson['offer_title'],
        offer_discription: paresdJson['offer_discription'],
        offer_detalis: paresdJson['offer_detalis'],
        offer_price: paresdJson['offer_price'],
        offer_discount: paresdJson['offer_discount'],
        is_discount: paresdJson['is_discount'],
        offer_photo:paresdJson['offer_photo']
    );
  }
}