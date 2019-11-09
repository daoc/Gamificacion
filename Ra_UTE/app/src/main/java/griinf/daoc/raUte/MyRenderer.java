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
import com.threed.jpct.SimpleVector;
import com.threed.jpct.Texture;
import com.threed.jpct.TextureManager;
import com.threed.jpct.World;
import com.threed.jpct.util.BitmapHelper;
import com.threed.jpct.util.MemoryHelper;

import java.io.InputStream;

import javax.microedition.khronos.egl.EGLConfig;
import javax.microedition.khronos.opengles.GL10;

public class MyRenderer implements GLSurfaceView.Renderer {
    enum Type3D {
        _3DS,
        _OBJ
    }

    private FrameBuffer fb;
    private World world;

    private Object3D cube;
    private int fps = 0;
    private long time = System.currentTimeMillis();
    private MainActivity master;

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
        if (master.touchTurn != 0) {
            cube.rotateY(master.touchTurn);
            master.touchTurn = 0;
        }

        if (master.touchTurnUp != 0) {
            cube.rotateX(master.touchTurnUp);
            master.touchTurnUp = 0;
        }

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
        world.setAmbientLight(20, 20, 20);
        Light sun = new Light(world);
        sun.setIntensity(250, 250, 250);

        loadTexture(R.drawable.monster, "monster");
        //loadTexture(R.drawable.pokeball, "pokeball");

        //cube = loadModel(R.raw.charizard, 0.9f, Type3D._OBJ, null);
        //cube = loadModel(R.raw.pokeball, 0.1f, Type3D._3DS, null);
        cube = loadModel(R.raw.hummingbird, 0.05f, Type3D._3DS, null);
        //cube = loadModel(R.raw.monster, 0.7f, Type3D._3DS, "monster");
        cube.build();

        world.addObject(cube);

        Camera cam = world.getCamera();
        cam.moveCamera(Camera.CAMERA_MOVEOUT, 50);
        cam.lookAt(cube.getTransformedCenter());

        SimpleVector sv = new SimpleVector();
        sv.set(cube.getTransformedCenter());
        sv.y -= 100;
        sv.z -= 100;
        sun.setPosition(sv);
        MemoryHelper.compact();
    }

    private void loadTexture(int ressource, String name) {
        Texture texture = new Texture(BitmapHelper.rescale(BitmapHelper.convert(master.getResources().getDrawable(ressource)), 512, 512));
        TextureManager.getInstance().addTexture(name, texture);
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