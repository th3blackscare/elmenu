
class MenuCategory{
  // ignore: non_constant_identifier_names
  String category_id;
  // ignore: non_constant_identifier_names
  String category_name;

  MenuCategory({
      this.category_id,
      this.category_name
    });
  factory MenuCategory.fromJson(Map<String, dynamic> paresdJson){
    return MenuCategory(
      category_id: paresdJson['category_id'],
      category_name: paresdJson['category_name']
    );
  }
}