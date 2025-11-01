import 'package:flutter/material.dart';

void main() {
  runApp(
      MyApp()
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      home: Scaffold(
        appBar: AppBar(
          title: Text("Latihan Stack"),
          centerTitle: true,
        ),
        body: Stack(
          alignment: Alignment.center,
          children: [
            Text("My Latihan"),
            Container(
              width: 200,
              height: 200,
              color: Colors.red,
            ),
            Container(
              width: 150,
              height: 150,
              color: Colors.green,
            ),
            Positioned(
              right: 10,
                bottom: 10,
                child: Container(
                  width: 150,
                  height: 150,
                  color: Colors.blue,
                  child: Image.network('https://imgs.search.brave.com/g87EeD1cfcdYCytu-V2wlJpglwqzqvH1LQ5tDLarJX0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YTEudGVub3IuY29t/L20vNXdrVG9pRm1M/VWNBQUFBZC9tb255/ZXQtdXBpbi1pcGlu/LmdpZg.gif'),
                ),
            ),
            CircleAvatar(
              radius: 200,
              backgroundColor: Colors.black,
              child: CircleAvatar(
                radius: 180,
                backgroundColor: Colors.pink,
                backgroundImage: NetworkImage('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSTreEbne-0RiQLsFO6v8R-ZokdFtZz8dXmmw&s'),
              ),
            ),
          ],
        ),
      ),
    );
  }
}