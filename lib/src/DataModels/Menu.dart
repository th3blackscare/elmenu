class MenuItem{

  // ignore: non_constant_identifier_names
  String item_id;
  // ignore: non_constant_identifier_names
  String item_name;
  // ignore: non_constant_identifier_names
  String item_details;
  // ignore: non_constant_identifier_names
  String item_price;
  // ignore: non_constant_identifier_names
  String item_photo;

  String category;

  MenuItem(
      {this.item_id,
      this.item_name,
      this.item_details,
      this.item_price,
      this.item_photo,
      this.category
      });
  factory MenuItem.fromJson(Map<String, dynamic> paresdJson){
    return MenuItem(
        item_id : paresdJson['item_id'],
        item_name : paresdJson['item_name'],
        item_details : paresdJson['item_details'],
        item_price : paresdJson['item_price'],
        item_photo : paresdJson['item_photo'],
        category : paresdJson['category']
    );
  }
}