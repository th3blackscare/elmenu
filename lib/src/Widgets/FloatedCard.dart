import 'package:flutter/material.dart';
import 'package:font_awesome_flutter/font_awesome_flutter.dart';



class CustomCard extends StatelessWidget {
  final Widget dragHandle;
  final BoxConstraints constraints;
  final Widget body;
  final bool beingDragged;

  const CustomCard({
    Key key,
    @required this.dragHandle,
    @required this.constraints,
    @required this.beingDragged,
    @required this.body,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Column(
      children: <Widget>[
        Material(
          elevation: beingDragged ? 6 : 20,
          borderOnForeground: true,
          clipBehavior: Clip.hardEdge,
          borderRadius: BorderRadius.only(
            topLeft: Radius.circular(15),
            topRight: Radius.circular(15),
          ),
          child: Container(
            decoration: BoxDecoration(
              color: Colors.white,
            ),
            width: 300,
            child: dragHandle,
          ),
        ),
        Expanded(
          child: Container(
            margin: EdgeInsets.only(bottom: 100),
            child: Material(
              elevation: beingDragged ? 18 : 4,
              borderRadius: BorderRadius.only(
                bottomLeft: Radius.circular(15),
                bottomRight: Radius.circular(15),
              ),
              child: Container(
                //padding: EdgeInsets.all(15),
                width: 300,
                child: body,
              ),
            ),
          ),
        ),
      ],
    );
  }
}

class CustomDrag extends StatelessWidget {
  final bool beingDragged;
  const CustomDrag({
    Key key,
    this.beingDragged,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return AnimatedContainer(
      curve: Curves.easeInCirc,
      duration: Duration(milliseconds: 300),
      padding: EdgeInsets.all(14),
      width: double.infinity,
      decoration: BoxDecoration(
        color: beingDragged ? Colors.red[200] : Colors.red[400],
        //borderRadius: BorderRadius.circular(15)
      ),
      child: Center(
        child: FaIcon(FontAwesomeIcons.infoCircle,color: Colors.white,),
      ),
    );
  }
}