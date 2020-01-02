package griinf.daoc.raUte;

import android.opengl.GLSurfaceView;
import android.support.annotation.Nullable;

import com.threed.jpct.Camera;
import com.threed.jpct.FrameBuffer;
import com.threed.jpct.Light;
import com.threed.jpct.Loader;
import com.threed.jpct.Logger;
import com.threed.jpct.Matrix;
import com.threed.jpct.Object3D;
import com.threed.jpct.Primitives;
import com.threed.jpct.RGBColor;
import com.threed.jpct.SimpleVector;
import com.threed.jpct.Texture;
import com.threed.jpct.TextureManager;
import com.threed.jpct.World;
import com.threed.jpct.util.BitmapHelper;
import com.threed.jpct.util.MemoryHelper;

import java.io.InputStream;
import java.util.Random;

import javax.microedition.khronos.egl.EGLConfig;
import javax.microedition.khronos.opengles.GL10;

public class MyRenderer implements GLSurfaceView.Renderer {
    Random rnd = new Random();

    enum Type3D {
        _3DS,
        _OBJ
    }

    private FrameBuffer fb;
    private World world;
    private Camera cam;
    private Object3D bala;
    private Object3D center;

    //private Object3D cube;
    private int fps = 0;
    private long time = System.currentTimeMillis();
    private MainActivity master;
    private Character charizard;
    private Bullet bullet;
    private Blast blast;

    public MyRenderer(MainActivity master) {
        this.master = master;
        buildWorld();
    }

    @Override
    public void onSurfaceChanged(GL10 gl, int w, int h) {
        if (fb != null) {
            fb.dispose();
        }
        fb = new FrameBuffer(gl, w, h);
    }

    @Override
    public void onSurfaceCreated(GL10 gl, EGLConfig config) {}

    @Override
    public void onDrawFrame(GL10 gl) {
        charizard.turn(master.touchTurn, master.touchTurnUp);
        master.touchTurn = 0; master.touchTurnUp = 0;

        charizard.move();
        bullet.move();
        blast.move();

        float proportion = 0.4f;

        //move dummy object instead of camera
        center.rotateX(-master.deltaRotationVector[1] * proportion);
        center.rotateY(master.deltaRotationVector[0] * proportion);

        fb.clear();
        world.renderScene(fb);
        world.draw(fb);
        fb.display();

        if (System.currentTimeMillis() - time >= 1000) {
            Logger.log(fps + "fps");
            fps = 0;
            time = System.currentTimeMillis();
        }
        fps++;
    }

    private void buildWorld() {
        world = new World();
        cam = world.getCamera();
        center = Object3D.createDummyObj();
        world.setAmbientLight(50, 50, 50);
        Light sun = new Light(world);
        sun.setIntensity(200, 200, 200);
        //sun.setPosition(new SimpleVector(origin.x, origin.y - 100, origin.z - 100));
        sun.setPosition(cam.getPosition());

        //loadTexture(R.drawable.monster, "monster");
        loadTexture(R.drawable.charizard, "charizard");
        loadTexture(R.drawable.bomb_explosion, "blast");



        charizard = new Character(master, Character.Type3D._OBJ, R.raw.charizard, 0.6f, null, "charizard");
        charizard.getObj().setOrigin(new SimpleVector(0, 0, 30));
        center.addChild(charizard.getObj());
        world.addObject(charizard.getObj());

        bullet = new Bullet(master);
        world.addObject(bullet.getObj());

        blast = new Blast(master, bullet, charizard);
        world.addObject(blast.getObj());

        MemoryHelper.compact();
    }

    private void loadTexture(int ressource, String name) {
        if(!TextureManager.getInstance().containsTexture(name)) {
            Texture texture = new Texture(BitmapHelper.rescale(BitmapHelper.convert(master.getResources().getDrawable(ressource)), 512, 512));
            TextureManager.getInstance().addTexture(name, texture);
        }
    }

    private Object3D loadModel(int ressource, float scale, Type3D type, @Nullable String texture) {
        InputStream stream = master.getResources().openRawResource(ressource);

        Object3D[] model = null;
        if (type == Type3D._3DS) {
            model = Loader.load3DS(stream, scale);
        } else if (type == Type3D._OBJ) {
            model = Loader.loadOBJ(stream, null, scale);
        }
        Object3D o3d = new Object3D(0);
        Object3D temp = null;
        for (int i = 0; i < model.length; i++) {
            temp = model[i];
            temp.setCenter(SimpleVector.ORIGIN);
            temp.rotateX((float) (-.5 * Math.PI));
            temp.rotateMesh();
            temp.setRotationMatrix(new Matrix());
            o3d = Object3D.mergeObjects(o3d, temp);
            o3d.build();
        }

        if(texture != null) {
            o3d.setTexture(texture);
        }

        return o3d;
    }
}